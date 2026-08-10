<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUsuarioController extends Controller
{
    /**
     * Display Admin Dashboard with User Management list, stats and roles.
     */
    public function index(): View
    {
        $usuarios = User::with('rol')->orderBy('id_usuario', 'desc')->paginate(15);
        $roles    = Rol::all();

        return view('admin.admin', compact('usuarios', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombres'   => 'required|string|max:150',
            'direccion' => 'required|string|max:255',
            'email'     => 'required|email|unique:usuarios,email',
            'password'  => 'required|string|min:4',
            'id_rol'    => 'required|exists:rol,id_rol',
            'telefono'  => 'nullable|string|max:20',
            'documento_numero' => 'nullable|string|max:50',
        ], [
            'nombres.required'   => 'El nombre completo es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'id_rol.required'    => 'Debe seleccionar un rol para el usuario.',
        ]);

        User::create([
            'nombres'          => $request->nombres,
            'direccion'        => $request->direccion,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'id_rol'           => $request->id_rol,
            'telefono'         => $request->telefono,
            'documento_numero' => $request->documento_numero,
            'estado'           => 1,
        ]);

        session()->flash('alert', [
            'icon'  => 'success',
            'title' => 'Éxito',
            'text'  => 'Usuario creado correctamente.',
        ]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Update an existing user's information.
     */
    public function update(Request $request, User $usuario): JsonResponse|RedirectResponse
    {
        $request->validate([
            'nombres'   => 'required|string|max:150',
            'direccion' => 'required|string|max:255',
            'id_rol'    => 'required|exists:rol,id_rol',
            'password'  => 'nullable|string|min:4',
        ]);

        $data = [
            'nombres'   => $request->nombres,
            'direccion' => $request->direccion,
            'id_rol'    => $request->id_rol,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $updated = $usuario->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            if ($updated) {
                return response()->json(['ok' => true, 'message' => 'Usuario actualizado correctamente.']);
            }
            return response()->json(['ok' => false, 'message' => 'No se pudo actualizar el usuario.'], 400);
        }

        session()->flash('alert', [
            'icon'  => $updated ? 'success' : 'error',
            'title' => $updated ? 'Éxito' : 'Error',
            'text'  => $updated ? 'Usuario actualizado correctamente.' : 'No se pudo actualizar el usuario.',
        ]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * Toggle user status (active / suspended).
     */
    public function toggleEstado(User $usuario): RedirectResponse
    {
        $nuevoEstado = ($usuario->estado == 1) ? 0 : 1;
        $usuario->update(['estado' => $nuevoEstado]);

        $statusText = ($nuevoEstado == 1) ? 'activado' : 'suspendido';

        session()->flash('alert', [
            'icon'  => 'success',
            'title' => 'Éxito',
            'text'  => "Usuario {$statusText} correctamente.",
        ]);

        return redirect()->route('admin.dashboard');
    }
}
