<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Idiomas soportados por la aplicación
     */
    protected $supportedLocales = ['es', 'en', 'fr'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener idioma de la sesión o usar por defecto
        $locale = Session::get('locale', config('app.locale', 'es'));

        // Validar que sea un idioma soportado
        if (in_array($locale, $this->supportedLocales)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
