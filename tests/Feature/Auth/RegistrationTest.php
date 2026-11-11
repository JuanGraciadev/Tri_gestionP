<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'nombres'               => 'Test Cliente',
            'email'                 => 'cliente@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'terms'                 => '1',
        ]);

        $response->assertRedirect(route('register'));
        $this->assertDatabaseHas('usuarios', [
            'email'   => 'cliente@example.com',
            'nombres' => 'Test Cliente',
            'id_rol'  => 3,
            'estado'  => 1,
        ]);
    }
}

