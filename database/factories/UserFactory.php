<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres'           => fake()->name(),
            'direccion'         => fake()->address(),
            'email'             => fake()->unique()->safeEmail(),
            'password'          => static::$password ??= Hash::make('password'),
            'id_rol'            => 3,
            'estado'            => 1,
            'intentos_fallidos' => 0,
            'remember_token'    => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an administrator.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_rol' => 1,
        ]);
    }

    /**
     * Indicate that the user is a trabajador.
     */
    public function trabajador(): static
    {
        return $this->state(fn (array $attributes) => [
            'id_rol' => 2,
        ]);
    }

    /**
     * Indicate that the user is inhabilitado.
     */
    public function inhabilitado(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 0,
        ]);
    }
}
