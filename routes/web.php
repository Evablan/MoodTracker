<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodEmotionController;
use App\Http\Controllers\LanguageController;


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




// Rutas para cambio de idioma (usando controlador profesional)

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])
    ->name('lang')
    ->where('locale', 'es|en|fr');
