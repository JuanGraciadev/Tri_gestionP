<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogoFiltrosTest extends TestCase
{
    use RefreshDatabase;

    protected User $cliente;
    protected Categoria $catAgua;
    protected Categoria $catHielos;
    protected Producto $prod1;
    protected Producto $prod2;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create(['id_rol' => 1, 'estado' => 1]);
        $this->cliente = User::factory()->create(['id_rol' => 3, 'estado' => 1]);

        $this->catAgua   = Categoria::create(['nombre' => 'Agua Purificada', 'estado' => 1]);
        $this->catHielos = Categoria::create(['nombre' => 'Hielo en Cubos', 'estado' => 1]);

        $this->prod1 = Producto::create([
            'nombre'       => 'Botellon Clasico',
            'precio'       => 10.00,
            'id_usuario'   => $admin->id_usuario,
            'id_categoria' => $this->catAgua->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 5,
        ]);

        $this->prod2 = Producto::create([
            'nombre'       => 'Bolsa Hielo Fiesta',
            'precio'       => 25.00,
            'id_usuario'   => $admin->id_usuario,
            'id_categoria' => $this->catHielos->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 5,
        ]);
    }

    /**
     * 2.5 Filtro por categoría existente muestra solo productos de esa categoría.
     */
    public function test_catalogo_filtra_por_categoria(): void
    {
        $response = $this->actingAs($this->cliente)->get('/catalogo?cat=' . $this->catAgua->id_categoria);

        $response->assertStatus(200);
        $response->assertSee('Botellon Clasico');
        $response->assertDontSee('Bolsa Hielo Fiesta');
    }

    /**
     * 2.5 Categoría que no existe muestra el mensaje específico.
     */
    public function test_catalogo_categoria_inexistente_muestra_mensaje_especifico(): void
    {
        $response = $this->actingAs($this->cliente)->get('/catalogo?cat=999999');

        $response->assertStatus(200);
        $response->assertSee('La categoría ingresada no existe');
    }

    /**
     * 2.5 Búsqueda por nombre o código encuentra el producto.
     */
    public function test_catalogo_busqueda_por_nombre_o_codigo(): void
    {
        // Por nombre
        $response = $this->actingAs($this->cliente)->get('/catalogo?buscar=Botellon');
        $response->assertStatus(200);
        $response->assertSee('Botellon Clasico');
        $response->assertDontSee('Bolsa Hielo Fiesta');

        // Por código / id_producto
        $responseId = $this->actingAs($this->cliente)->get('/catalogo?buscar=' . $this->prod2->id_producto);
        $responseId->assertStatus(200);
        $responseId->assertSee('Bolsa Hielo Fiesta');
        $responseId->assertDontSee('Botellon Clasico');
    }

    /**
     * 2.5 Búsqueda de producto que no existe muestra el mensaje específico.
     */
    public function test_catalogo_busqueda_inexistente_muestra_mensaje_especifico(): void
    {
        $response = $this->actingAs($this->cliente)->get('/catalogo?buscar=InexistenteXYZ');

        $response->assertStatus(200);
        $response->assertSee('El producto ingresado no existe');
    }

    /**
     * 2.5 Filtro por rango de precio (mínimo / máximo).
     */
    public function test_catalogo_filtro_por_rango_de_precio(): void
    {
        // Precios entre $15 y $30 -> Solo el Hielo ($25)
        $response = $this->actingAs($this->cliente)->get('/catalogo?precio_min=15&precio_max=30');

        $response->assertStatus(200);
        $response->assertSee('Bolsa Hielo Fiesta');
        $response->assertDontSee('Botellon Clasico');
    }
}
