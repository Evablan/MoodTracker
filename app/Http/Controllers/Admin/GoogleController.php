<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Company;

class GoogleController extends Controller
{
    // 1) Te llevo a Google a iniciar sesión
    public function redirect()
    {
        // scopes básicos: email y perfil
        return Socialite::driver('google')->scopes(['openid', 'profile', 'email'])->redirect();
    }

    // 2) Google te devuelve aquí con tu "pase"
    public function callback()
    {
        try {
            // Recogemos los datos que envía Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            // Si falla el estado, intentamos de nuevo sin estado (stateless)
            // Esto puede pasar si la sesión se perdió entre el redirect y el callback
            $googleUser = Socialite::driver('google')->stateless()->user();
        }

        // A veces (muy raro) no devuelve email -> paramos con mensaje claro
        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect('/')->with('error', 'Tu cuenta de Google no devuelve email verificado.');
        }

        // Obtener la primera compañía (o usar una lógica diferente según tu caso)
        $company = Company::firstOrFail();

        // ¿Existe en nuestra BD? si no, lo creamos
        $user = User::firstOrCreate(
            ['email' => $email, 'company_id' => $company->id],
            [
                'name'              => $googleUser->getName() ?: 'Usuario Google',
                'email_verified_at' => now(),
                'role'              => 'employee',
                'company_id'        => $company->id,
            ]
        );

        // Iniciamos sesión
        Auth::login($user);

        // Obtener el rol del usuario (por si acaso no tiene rol asignado)
        $role = $user->role ?? 'employee';

        // Solo employees necesitan consentimiento
        if ($role === 'employee' && is_null($user->consent_at)) {
            return redirect()->to('/consent');
        }

        // Redirigir según el rol
        if ($role === 'employee') {
            return redirect()->to('/moods/create');
        }

        // Para admin, rrhh, etc. ir al dashboard (sin necesidad de consentimiento)
        return redirect()->to('/dashboard');
    }
}
