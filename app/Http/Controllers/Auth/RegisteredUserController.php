<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:150'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:'.User::class.',email'],
            'documento_numero' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ]);

        $user = User::create([
            'nombres' => $request->nombres,
            'direccion' => $request->direccion,
            'email' => $request->email,
            'documento_numero' => $request->documento_numero,
            'telefono' => $request->telefono,
            'password' => Hash::make($request->password),
            'id_rol' => 3, // Cliente por defecto
            'estado' => 1,
        ]);

        event(new Registered($user));

        return redirect()->route('register')->with('success', '¡Registro completado con éxito! Tu cuenta ha sido creada correctamente.');
    }
}
