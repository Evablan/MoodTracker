<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureUserConsented
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Si no está logueado, o ya consintió, o está en la propia ruta de consentimiento → pasa
        if (!$user || $user->consent_at || $request->is('consent*')) {
            return $next($request);
        }

        // Solo fuerzo a empleados. Admin/manager pueden entrar sin consentimiento
        if (($user->role ?? 'employee') === 'employee') {
            return redirect('/consent');
        }

        return $next($request);
    }
}
