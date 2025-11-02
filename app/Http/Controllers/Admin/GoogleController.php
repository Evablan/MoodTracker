<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    // 1) Te llevo a Google a iniciar sesión
    public function redirect()
    {
        // scopes básicos: email y perfil
        return Socialite::driver('google')->scopes(['openid', 'profile', 'email'])->redirect();
    }

    // 2) Google te devuelve aquí con tu “pase”
    public function callback()
    {
        // Recogemos los datos que envía Google
        $googleUser = Socialite::driver('google')->user();

        // A veces (muy raro) no devuelve email -> paramos con mensaje claro
        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect('/')->with('error', 'Tu cuenta de Google no devuelve email verificado.');
        }

        // ¿Existe en nuestra BD? si no, lo creamos
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'              => $googleUser->getName() ?: 'Usuario Google',
                'email_verified_at' => now(),
                // Si usas roles por columna:
                'role'              => 'employee', // puedes ajustarlo luego
            ]
        );

        // Iniciamos sesión
        Auth::login($user);

        // Si es empleado y no ha dado consentimiento, lo mandamos a /consent
        if ($user->role === 'employee' && is_null($user->consent_at)) {
            return redirect()->to('/consent');
        }

        // Si no, al dashboard que toque
        return redirect()->to('/dashboard');
    }
}
