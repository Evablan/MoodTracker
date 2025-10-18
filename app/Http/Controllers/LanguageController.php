<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Idiomas soportados
     */
    private $supportedLocales = ['es', 'en', 'fr'];

    /**
     * Cambiar idioma de la aplicación
     */
    public function switch($locale)
    {
        // Validar que el idioma sea soportado
        if (in_array($locale, $this->supportedLocales)) {
            // Guardar en sesión
            Session::put('locale', $locale);

            // Mensaje de confirmación (opcional)
            Session::flash('message', "Idioma cambiado a: " . $this->getLanguageName($locale));
        }

        // Redirigir a la página anterior
        return redirect()->back();
    }

    /**
     * Obtener nombre del idioma
     */
    private function getLanguageName($locale)
    {
        $languages = [
            'es' => 'Español',
            'en' => 'English',
            'fr' => 'Français'
        ];

        return $languages[$locale] ?? $locale;
    }
}
