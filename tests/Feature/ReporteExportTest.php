<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReporteExportTest extends TestCase
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

    public function test_invitados_no_pueden_exportar_reportes(): void
    {
        $this->get(route('reportes.exportar.csv'))->assertRedirect(route('login'));
        $this->get(route('reportes.exportar.excel'))->assertRedirect(route('login'));
    }

    public function test_trabajador_y_cliente_no_tienen_permiso_de_exportar(): void
    {
        // En peticiones web normales, CheckRole redirige al dashboard respectivo por falta de permiso
        $this->actingAs($this->trabajador)->get(route('reportes.exportar.csv'))->assertRedirect(route('trabajador.dashboard'));
        $this->actingAs($this->trabajador)->get(route('reportes.exportar.excel'))->assertRedirect(route('trabajador.dashboard'));

        $this->actingAs($this->cliente)->get(route('reportes.exportar.csv'))->assertRedirect(route('cliente.dashboard'));
        $this->actingAs($this->cliente)->get(route('reportes.exportar.excel'))->assertRedirect(route('cliente.dashboard'));

        // En peticiones JSON, CheckRole devuelve 403
        $this->actingAs($this->trabajador)->getJson(route('reportes.exportar.csv'))->assertForbidden();
        $this->actingAs($this->cliente)->getJson(route('reportes.exportar.excel'))->assertForbidden();
    }

    public function test_admin_puede_exportar_reporte_csv(): void
    {
        $cat = Categoria::create(['nombre' => 'Botellones', 'estado' => 1]);
        Producto::create([
            'nombre'       => 'Botellón 20L',
            'precio'       => 15.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $cat->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);

        $response = $this->actingAs($this->admin)->get(route('reportes.exportar.csv', ['filtro' => '30dias']));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('reporte_trigestion_30dias', $response->headers->get('Content-Disposition'));
    }

    public function test_admin_puede_exportar_reporte_excel(): void
    {
        $cat = Categoria::create(['nombre' => 'Bolsas', 'estado' => 1]);
        Producto::create([
            'nombre'       => 'Bolsa de Agua 5L',
            'precio'       => 5.00,
            'id_usuario'   => $this->admin->id_usuario,
            'id_categoria' => $cat->id_categoria,
            'estado'       => 1,
            'stock_minimo' => 10,
        ]);

        $response = $this->actingAs($this->admin)->get(route('reportes.exportar.excel', ['filtro' => '7dias']));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/vnd.ms-excel', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('reporte_trigestion_7dias', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('TRIGESTION - REPORTE EJECUTIVO Y CONSOLIDADO', $response->getContent());
        $this->assertStringContainsString('Bolsa de Agua 5L', $response->getContent());
    }

    public function test_vista_reportes_muestra_botones_de_exportacion(): void
    {
        $response = $this->actingAs($this->admin)->get(route('reportes.index'));

        $response->assertStatus(200);
        $response->assertSee(route('reportes.exportar.excel'));
        $response->assertSee(route('reportes.exportar.csv'));
        $response->assertSee('generarPDF()', false);
    }
}

