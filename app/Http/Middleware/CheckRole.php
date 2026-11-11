<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:1') or middleware('role:1,2')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user && (int) $user->estado === 0) {
            \Illuminate\Support\Facades\Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu usuario está inhabilitado. Contacta al administrador.',
            ]);
        }

        if (!$user || !in_array((string) $user->id_rol, $roles, true)) {
            // Redirect to the correct dashboard based on their actual role
            return match ((int) $user?->id_rol) {
                1 => redirect()->route('admin.dashboard'),
                2 => redirect()->route('trabajador.dashboard'),
                3 => redirect()->route('cliente.dashboard'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}
