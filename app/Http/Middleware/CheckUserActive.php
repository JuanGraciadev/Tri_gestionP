<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     * If an authenticated user has estado === 0 (disabled), log them out immediately.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ((int) $user->estado === 0) {
                Auth::guard('web')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Tu usuario está inhabilitado. Contacta al administrador.',
                    ], 403);
                }

                return redirect()->route('login')->withErrors([
                    'email' => 'Tu usuario está inhabilitado. Contacta al administrador.',
                ]);
            }
        }

        return $next($request);
    }
}
