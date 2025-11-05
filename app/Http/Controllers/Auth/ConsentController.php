<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    /**
     * Mostrar el formulario de consentimiento
     */
    public function show()
    {
        return view('auth.consent');
    }

    /**
     * Guardar el consentimiento del usuario
     */
    public function store(Request $request)
    {
        // Validar que el usuario aceptó los términos
        $request->validate([
            'accept_terms' => 'required|accepted'  // Debe ser "1" o "on"
        ]);

        // Actualizar el campo consent_at del usuario autenticado
        $user = $request->user();
        $user->update([
            'consent_at' => now()
        ]);

        // Recargar el modelo para que el middleware vea los cambios
        $user->refresh();

        // Redirigir según el rol
        if ($user->role === 'employee') {
            return redirect('/moods/create')
                ->with('success', 'Has aceptado los términos y condiciones correctamente.');
        }

        // Para admin, rrhh, etc. ir al dashboard
        return redirect('/dashboard')
            ->with('success', 'Has aceptado los términos y condiciones correctamente.');
    }
}
