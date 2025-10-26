<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodEmotionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

/*Página de dashboard user*/
Route::get('/dashboard', function () {
    return view('dashboard');
});

/*Página de mood emotion formulario user*/
Route::prefix('moods')->name('moods.')->controller(MoodEmotionController::class)->group(function () {
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

    if (auth()->attempt($credentials)) {
        request()->session()->regenerate();
        return redirect()->intended('/admin/dashboard');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden.',
    ])->withInput();
})->name('login.post');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Dashboard Admin, rrhh
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', fn() => redirect('/admin/dashboard'));
    Route::get('/admin/dashboard', [DashboardController::class, 'overview'])->name('admin.dashboard');
});




// Rutas para cambio de idioma (usando controlador profesional)

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang')
    ->where('locale', 'es|en|fr');
