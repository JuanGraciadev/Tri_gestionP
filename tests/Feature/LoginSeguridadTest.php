<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginSeguridadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1.1 Usuario inhabilitado (estado = 0) no puede ingresar aunque su contraseña sea correcta.
     */
    public function test_usuario_inhabilitado_no_puede_iniciar_sesion(): void
    {
        $user = User::factory()->create([
            'email'    => 'inhabilitado@example.com',
            'password' => Hash::make('password123'),
            'estado'   => 0,
            'id_rol'   => 3,
        ]);

        $response = $this->post('/login', [
            'email'    => 'inhabilitado@example.com',
            'password' => 'password123',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Tu usuario está inhabilitado. Contacta al administrador.'
        ]);
    }

    /**
     * 1.1 Si un usuario es inhabilitado mientras tiene sesión abierta, se le cierra en su siguiente petición.
     */
    public function test_usuario_inhabilitado_con_sesion_activa_es_desconectado(): void
    {
        $user = User::factory()->create([
            'estado' => 1,
            'id_rol' => 3,
        ]);

        // Autenticar usuario activo
        $this->actingAs($user);
        $this->assertAuthenticated();

        // Se inhabilita en la BD
        $user->update(['estado' => 0]);

        // Siguiente petición pasa por el middleware CheckUserActive
        $response = $this->get('/mis-compras');

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'Tu usuario está inhabilitado. Contacta al administrador.'
        ]);
    }

    /**
     * 1.2 Bloqueo tras 3 intentos fallidos con contraseña incorrecta.
     */
    public function test_tres_intentos_fallidos_bloquean_la_cuenta(): void
    {
        $user = User::factory()->create([
            'email'             => 'bloqueable@example.com',
            'password'          => Hash::make('secreta123'),
            'estado'            => 1,
            'intentos_fallidos' => 0,
        ]);

        // Intento 1
        $this->post('/login', ['email' => $user->email, 'password' => 'bad1']);
        $user->refresh();
        $this->assertEquals(1, $user->intentos_fallidos);
        $this->assertEquals(1, $user->estado);

        // Intento 2
        $this->post('/login', ['email' => $user->email, 'password' => 'bad2']);
        $user->refresh();
        $this->assertEquals(2, $user->intentos_fallidos);
        $this->assertEquals(1, $user->estado);

        // Intento 3 -> Debe bloquear
        $response = $this->post('/login', ['email' => $user->email, 'password' => 'bad3']);
        $user->refresh();
        $this->assertEquals(3, $user->intentos_fallidos);
        $this->assertEquals(0, $user->estado);

        $this->assertGuest();
        $response->assertSessionHasErrors([
            'email' => 'Cuenta bloqueada por intentos fallidos. Contacta al administrador.'
        ]);
    }

    /**
     * 1.2 Login correcto reinicia el contador de intentos fallidos a 0.
     */
    public function test_login_correcto_reinicia_contador_de_intentos_fallidos(): void
    {
        $user = User::factory()->create([
            'email'             => 'reinicio@example.com',
            'password'          => Hash::make('correcta123'),
            'estado'            => 1,
            'intentos_fallidos' => 2,
            'id_rol'            => 3,
        ]);

        $response = $this->post('/login', [
            'email'    => 'reinicio@example.com',
            'password' => 'correcta123',
        ]);

        $this->assertAuthenticated();
        $user->refresh();
        $this->assertEquals(0, $user->intentos_fallidos);
    }
}
