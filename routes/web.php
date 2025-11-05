<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodEmotionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GoogleController;
use App\Http\Controllers\Auth\ConsentController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/redirect/google', [GoogleController::class, 'redirect'])
    ->name('google.redirect');

Route::get('/auth/callback/google', [GoogleController::class, 'callback'])
    ->name('google.callback');

// Página de login con botones SSO
Route::view('/login', 'auth.login')->name('login')->middleware('guest');

// Rutas de consentimiento (solo auth, NO consented)
Route::get('/consent', [ConsentController::class, 'show'])
    ->name('consent.show')
    ->middleware('auth');

Route::post('/consent', [ConsentController::class, 'store'])
    ->name('consent.store')
    ->middleware('auth');

/*Página de dashboard user*/
Route::get('/dashboard', [DashboardController::class, 'overview'])
    ->middleware('auth', 'consented')
    ->name('user.dashboard');

/*Página de mood emotion formulario user*/
Route::prefix('moods')->name('moods.')->controller(MoodEmotionController::class)
    ->middleware('auth', 'consented')
    ->group(function () {
        Route::get('/create', 'create')->name('create'); // Página de formulario de mood emotion
        Route::post('/', 'store')->name('store'); // Guardar el formulario de mood emotion
    });

// Rutas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        request()->session()->regenerate();
        $user = Auth::user(); // Obtener el usuario autenticado

        // Redirigir según el rol
        $role = $user->role ?? 'employee';

        // Solo employees necesitan consentimiento
        if ($role === 'employee' && is_null($user->consent_at)) {
            return redirect()->route('consent.show');
        }

        // Redirigir según el rol
        if ($role === 'employee') {
            return redirect()->intended('/moods/create');
        }

        // Para admin, rrhh, etc. ir al dashboard (sin necesidad de consentimiento)
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden.',
    ])->withInput();
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Dashboard Admin, rrhh
Route::middleware(['auth', 'consented'])->group(function () {
    Route::get('/admin', fn() => redirect('/admin/dashboard'));
    Route::get('/admin/dashboard', [DashboardController::class, 'overview'])->name('admin.dashboard');
});




// Rutas para cambio de idioma (usando controlador profesional)

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang')
    ->where('locale', 'es|en|fr');
